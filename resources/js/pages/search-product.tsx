import ProductCard from '@/components/product-card';
import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';
import { useMemo } from 'react';

type Product = {
    id: number;
    name: string;
    description: string;
    price: number;
};

const sampleProducts: Product[] = [
    {
        id: 1,
        name: 'コットンTシャツ',
        price: 2500,
        description: 'ベーシックな厚手コットン',
    },
    {
        id: 2,
        name: 'デニムパンツ',
        price: 7200,
        description: 'ストレートシルエット',
    },
    { id: 3, name: 'スニーカー', price: 9800, description: '軽量ランニング' },
    { id: 4, name: 'ニット帽', price: 1800, description: '秋冬向け' },
    { id: 5, name: 'トートバッグ', price: 3400, description: 'キャンバス素材' },
    { id: 6, name: 'サングラス', price: 5600, description: 'UVカット' },
];

export default function SearchProduct() {
    const keyword = 'コットン';

    const results = useMemo(
        () =>
            sampleProducts.filter((p) =>
                [p.name, p.description]
                    .join(' ')
                    .toLowerCase()
                    .includes(keyword.toLowerCase()),
            ),
        [keyword],
    );

    return (
        <AppLayout>
            <Head title={keyword} />

            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <p className="mt-1 text-sm text-slate-600">
                            検索キーワード:{' '}
                            <span className="font-medium text-slate-800">
                                {keyword}
                            </span>
                        </p>
                    </div>

                    <div className="text-sm text-slate-600">
                        検索結果: {results.length} 件
                    </div>
                </div>

                {results.length === 0 ? (
                    <div className="mt-6 rounded-lg border border-dashed border-slate-200 bg-white p-10 text-center text-sm text-slate-500">
                        条件に一致する商品が見つかりません。
                    </div>
                ) : (
                    <div className="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        {results.map((p) => (
                            <ProductCard
                                key={p.id}
                                id={p.id}
                                name={p.name}
                                description={p.description}
                                price={p.price}
                            />
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
