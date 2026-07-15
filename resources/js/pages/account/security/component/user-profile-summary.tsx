interface Props {
    name: string;
    email: string;
}

export function UserProfileSummary({ name, email }: Props) {
    return (
        <div className="flex flex-col gap-3 rounded-lg border border-slate-200 bg-slate-50 p-4">
            <div className="flex flex-col gap-1">
                <p className="text-sm font-medium text-slate-700">ユーザー名</p>

                <p className="text-base font-semibold text-slate-900">{name}</p>
            </div>

            <div className="flex flex-col gap-1">
                <p className="text-sm font-medium text-slate-700">
                    メールアドレス
                </p>

                <p className="text-base font-semibold text-slate-900">
                    {email}
                </p>
            </div>
        </div>
    );
}
