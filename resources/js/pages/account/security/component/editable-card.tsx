import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';

interface Props {
    title: string;
    description: string;
    isEditing: boolean;
    onEdit: () => void;
    view: React.ReactNode;
    edit: React.ReactNode;
    editLabel?: string;
}

export function EditableCard({
    title,
    description,
    isEditing,
    onEdit,
    view,
    edit,
    editLabel = '編集',
}: Props) {
    return (
        <Card>
            <CardHeader>
                <div className="flex items-center justify-between">
                    <div>
                        <CardTitle>{title}</CardTitle>
                        <CardDescription>{description}</CardDescription>
                    </div>

                    {!isEditing && (
                        <Button
                            type="button"
                            variant="primary"
                            onClick={onEdit}
                        >
                            {editLabel}
                        </Button>
                    )}
                </div>
            </CardHeader>

            <CardContent>{isEditing ? edit : view}</CardContent>
        </Card>
    );
}
