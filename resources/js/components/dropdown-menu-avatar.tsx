import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { UserInfo } from '@/components/user-info';
import { logout } from '@/routes';
import account from '@/routes/account';
import { User } from '@/types';
import { Form, Link } from '@inertiajs/react';
import { BadgeCheckIcon, LogOutIcon } from 'lucide-react';

export default function DropdownMenuAvatar({ user }: { user: User }) {
    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button variant="ghost" size="icon" className="rounded-full">
                    <UserInfo user={user} />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
                <DropdownMenuGroup>
                    <DropdownMenuItem asChild>
                        <Link href={account.index().url}>
                            <BadgeCheckIcon />
                            Account
                        </Link>
                    </DropdownMenuItem>
                </DropdownMenuGroup>
                <DropdownMenuSeparator />
                <DropdownMenuItem asChild>
                    <Form {...logout.form()}>
                        <button
                            type="submit"
                            className="flex w-full items-center gap-2"
                        >
                            <LogOutIcon />
                            Sign Out
                        </button>
                    </Form>
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
