import { show } from '@/routes/product';
import { Product } from '@/types/product';
import { Link } from '@inertiajs/react';

interface ProductCardProps {
    product: Product;
}

export default function ProductCard({ product }: ProductCardProps) {
    return (
        <Link
            key={product.id}
            href={show(product.id)}
            className="block overflow-hidden rounded-lg border border-slate-200 bg-white transition hover:shadow-md"
        >
            <div className="flex h-40 w-full items-center justify-center bg-slate-100">
                <span className="text-sm text-slate-400">画像準備中</span>
            </div>

            <div className="p-3">
                <h2 className="text-sm font-medium text-slate-800">
                    {product.name}
                </h2>
                <p className="mt-1 line-clamp-4 text-xs text-slate-500">
                    {product.description}
                </p>
                <div className="mt-3 flex items-center gap-x-4">
                    <span className="text-sm font-semibold text-slate-800">
                        {product.price.toLocaleString('ja-JP')}円
                    </span>
                    {product.stockStatus['status'] === 'lowStock' && (
                        <span className="text-sm font-semibold text-red-600">
                            {product.stockStatus['label']}
                        </span>
                    )}
                </div>
            </div>
        </Link>
    );
}
