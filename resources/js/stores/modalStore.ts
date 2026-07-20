import { Address } from '@/types/address';
import { OrderItem } from '@/types/order-item';
import { create } from 'zustand';

/*
|--------------------------------------------------------------------------
| Modal props 定義
|--------------------------------------------------------------------------
*/

export interface ModalPropsMap {
    deleteReviewConfirm: {
        id: number;
    };

    cancelOrderConfirm: {
        id: number;
        orderNumber: string;
        totalAmount: number;
        items: OrderItem[];
    };

    createShippingAddress: undefined;

    editShippingAddress: Address;

    deleteShippingAddress: {
        id: number;
    };
}

/*
|--------------------------------------------------------------------------
| ModalType
|--------------------------------------------------------------------------
*/

export type ModalType = keyof ModalPropsMap;

/*
|--------------------------------------------------------------------------
| ModalState
|--------------------------------------------------------------------------
*/

export type ModalState =
    | {
          type: null;
          props: null;
      }
    | {
          [K in ModalType]: ModalPropsMap[K] extends undefined
              ? {
                    type: K;
                    props: null;
                }
              : {
                    type: K;
                    props: ModalPropsMap[K];
                };
      }[ModalType];

/*
|--------------------------------------------------------------------------
| Store interface
|--------------------------------------------------------------------------
*/

interface ModalStore {
    modal: ModalState;

    openModal: <T extends ModalType>(type: T, props?: ModalPropsMap[T]) => void;

    closeModal: () => void;
}

/*
|--------------------------------------------------------------------------
| Zustand store
|--------------------------------------------------------------------------
*/

export const useModalStore = create<ModalStore>((set) => ({
    modal: {
        type: null,
        props: null,
    },

    openModal: (type, props) =>
        set({
            modal: {
                type,
                props: props ?? null,
            } as ModalState,
        }),

    closeModal: () =>
        set({
            modal: {
                type: null,
                props: null,
            },
        }),
}));
