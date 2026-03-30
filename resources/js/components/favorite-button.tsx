import { store } from '@/routes/favorites';
import { router } from '@inertiajs/react';
import { Star } from 'lucide-react';
import React from 'react';
import { Button } from './ui/button';

interface FavoriteButtonProps {
    productId: number;
}

export function FavoriteButton({ productId }: FavoriteButtonProps) {
    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();

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
                <Star className="size-6 fill-slate-300 text-slate-300" />
            </Button>
        </form>
    );
}
