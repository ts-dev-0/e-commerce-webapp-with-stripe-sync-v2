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
        <form onSubmit={handleSubmit}>
            <div className="flex items-center gap-2">
                <Input
                    type="search"
                    placeholder="Search"
                    className="rounded-md border border-slate-300 px-2 py-1 text-sm placeholder-slate-400 focus:outline-none"
                    value={data.keyword}
                    onChange={(e) => setData({ keyword: e.target.value })}
                />
                <Button
                    type="submit"
                    className="bg-emerald-600 hover:bg-emerald-700"
                >
                    検索
                </Button>
            </div>
        </form>
    );
}
