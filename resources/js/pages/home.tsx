import ProductCard from '@/components/product-card';
import AppLayout from '@/layouts/app-layout';
import { Product } from '@/types/product';
import { Head } from '@inertiajs/react';

interface HomeProps {
    data: Product[];
}

export default function Home({ data }: HomeProps) {
    const products = data;

    return (
        <AppLayout>
            <Head title="Home" />

            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                {products.map((p) => (
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
