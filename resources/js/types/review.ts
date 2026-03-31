import { User } from ".";

export interface Review {
    id: number;
    user_id: number;
    rating: number;
    comment: string;
    createdAt: string;
    isEdited: boolean;
    user: Pick<User, 'id' | 'name'>
}
