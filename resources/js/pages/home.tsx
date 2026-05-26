import ProductCard from '@/components/product-card';
import AppLayout from '@/layouts/app-layout';
import { Product } from '@/types/product';
import { Head } from '@inertiajs/react';

interface HomeProps {
    products: Product[];
}

export default function Home({ products }: HomeProps) {
    return (
        <AppLayout>
            <Head title="Home" />

            <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                {products.map((product) => (
                    <ProductCard key={product.id} product={product} />
                ))}
            </div>
        </AppLayout>
    );
}
