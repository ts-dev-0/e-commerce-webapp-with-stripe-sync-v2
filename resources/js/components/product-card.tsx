import { show } from '@/routes/product';
import { Link } from '@inertiajs/react';
import { FavoriteButton } from './favorite-button';

interface ProductCardProps {
    id: number;
    name: string;
    description: string;
    price: number;
}

export default function ProductCard({
    id,
    name,
    description,
    price,
}: ProductCardProps) {
    return (
        <Link
            key={id}
            href={show(id)}
            className="block overflow-hidden rounded-lg border border-slate-200 bg-white transition hover:shadow-md"
        >
            <div className="flex h-40 w-full items-center justify-center bg-slate-100">
                <span className="text-sm text-slate-400">画像準備中</span>
            </div>

            <div className="p-3">
                <h2 className="text-sm font-medium text-slate-800">{name}</h2>
                <p className="mt-1 line-clamp-4 text-xs text-slate-500">
                    {description}
                </p>
                <div className="mt-3 flex items-center justify-between">
                    <span className="text-sm font-semibold text-slate-800">
                        {price.toLocaleString('ja-JP')}円
                    </span>
                    <FavoriteButton productId={id} isFavorited />
                </div>
            </div>
        </Link>
    );
}
