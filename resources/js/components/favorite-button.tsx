import { destroy, store } from '@/routes/favorites';
import { router } from '@inertiajs/react';
import { Star } from 'lucide-react';
import React from 'react';
import { Button } from './ui/button';

interface FavoriteButtonProps {
    productId: number;
    isFavorited: boolean;
}

export function FavoriteButton({
    productId,
    isFavorited,
}: FavoriteButtonProps) {
    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();

        if (isFavorited) {
            router.delete(destroy().url, {
                data: {
                    product_id: productId,
                },
            });
            return;
        }

        router.post(store().url, {
            product_id: productId,
        });
    }

    return (
        <form onSubmit={handleSubmit}>
            <Button
                size={'icon'}
                variant={'ghost'}
                className="cursor-pointer"
                type="submit"
            >
                {isFavorited ? (
                    <Star className="size-6 fill-yellow-300 text-yellow-300" />
                ) : (
                    <Star className="size-6 fill-slate-300 text-slate-300" />
                )}
            </Button>
        </form>
    );
}
