import search from '@/routes/search';
import { useForm } from '@inertiajs/react';
import React from 'react';
import { Button } from './ui/button';
import { Input } from './ui/input';

interface SearchProductsForm {
    keyword: string;
}

export function SearchProductsForm() {
    const { data, setData, get, reset } = useForm<SearchProductsForm>({
        keyword: '',
    });

    function handleSubmit(e: React.FormEvent) {
        e.preventDefault();

        if (data.keyword.length === 0) return;
        get(search.products().url, {
            onSuccess: () => {
                reset();
            },
        });
    }

    return (
        <div className="w-full px-2">
            <form onSubmit={handleSubmit} className="flex items-center gap-2">
                <Input
                    type="search"
                    placeholder="キーワード検索"
                    className="rounded-md border border-slate-200 px-2 py-1 text-sm placeholder-slate-400 focus:outline-none bg-white"
                    value={data.keyword}
                    onChange={(e) => setData({ keyword: e.target.value })}
                />
                <Button type="submit" variant="primary">
                    検索
                </Button>
            </form>
        </div>
    );
}
