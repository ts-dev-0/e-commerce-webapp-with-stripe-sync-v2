export function PasswordSummary() {
    return (
        <div className="flex items-center justify-between gap-4 rounded-lg border border-slate-200 bg-slate-50 p-4">
            <div className="flex flex-col gap-1">
                <p className="text-sm font-medium text-slate-700">
                    現在のパスワード
                </p>

                <p className="text-base font-semibold tracking-widest text-slate-900">
                    ********
                </p>
            </div>
        </div>
    );
}
