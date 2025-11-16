import { toast } from "vue3-toastify";

export function useToast() {
    return {
        success: (msg) => toast.success(msg),
        error: (msg) => toast.error(msg),
        info: (msg) => toast.info(msg),
        warn: (msg) => toast.warn(msg),
        validationError: (error) => {
            if (
                error?.response?.status === 422 &&
                error.response.data?.errors
            ) {
                const errors = error.response.data.errors;
                Object.values(errors).forEach((messages) => {
                    messages.forEach((msg) => toast.error(msg));
                });
            } else {
                toast.error(error?.message || "Something went wrong");
            }
        },
    };
}
