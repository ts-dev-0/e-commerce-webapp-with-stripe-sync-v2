import { useModalStore } from '@/stores/modalStore';
import { useEffect } from 'react';

interface Props {
    children: React.ReactNode;
}

export default function ModalWrapper({ children }: Props) {
    const closeModal = useModalStore((state) => state.closeModal);

    useEffect(() => {
        function handleKeyDown(e: KeyboardEvent) {
            if (e.key === 'Escape') {
                closeModal();
            }
        }

        window.addEventListener('keydown', handleKeyDown);

        return () => {
            window.removeEventListener('keydown', handleKeyDown);
        };
    }, [closeModal]);

    return (
        <div
            onClick={closeModal}
            className="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
        >
            <div
                onClick={(e) => e.stopPropagation()}
                className="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl"
            >
                {children}
            </div>
        </div>
    );
}
