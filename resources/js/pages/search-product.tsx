import ProductCard from '@/components/product-card';
import AppLayout from '@/layouts/app-layout';
import { Product } from '@/types/product';
import { Head } from '@inertiajs/react';

interface SearchProductProps {
    products: Product[];
    keyword: string;
}

export default function SearchProduct({
    products,
    keyword,
}: SearchProductProps) {
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
                        検索結果: {products.length} 件
                    </div>
                </div>

                {products.length === 0 ? (
                    <div className="mt-6 rounded-lg border border-dashed border-slate-200 bg-white p-10 text-center text-sm text-slate-500">
                        条件に一致する商品が見つかりません。
                    </div>
                ) : (
                    <div className="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        {products.map((product) => (
                            <ProductCard key={product.id} product={product} />
                        ))}
                    </div>
                )}
            </div>
        </AppLayout>
    );
}
