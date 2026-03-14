import ProductCard from '@/components/product-card';
import AppLayout from '@/layouts/app-layout';
import { Head } from '@inertiajs/react';

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

export default function Home() {
    return (
        <AppLayout>
            <Head title="Home" />

            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                {sampleProducts.map((p) => (
                    <ProductCard
                        key={p.id}
                        id={p.id}
                        name={p.name}
                        description={p.description}
                        price={p.price}
                    />
                ))}
            </div>
        </AppLayout>
    );
}
