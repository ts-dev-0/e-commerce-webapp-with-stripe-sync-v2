import { EmptyState } from '@/components/empty-state';
import AppLayout from '@/layouts/app-layout';
import { ShopLayout } from '@/layouts/shop-layout';
import ProductCard from '@/pages/shop/component/product-card';
import { Product } from '@/types/product';

interface Props {
    products: Product[];
}

export default function Index({ products }: Props) {
    return (
        <AppLayout>
            <ShopLayout title="トップページ">
                {products.length === 0 ? (
                    <EmptyState title="現在、ご紹介できる商品がありません" />
                ) : (
                    <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        {products.map((product) => (
                            <ProductCard key={product.id} product={product} />
                        ))}
                    </div>
                )}
            </ShopLayout>
        </AppLayout>
    );
}
