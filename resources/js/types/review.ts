import { User } from ".";

export interface Review {
    id: number;
    userId: number;
    rating: number;
    comment: string;
    createdAt: string;
    isEdited: boolean;
    user: Pick<User, 'id' | 'name'>
}
