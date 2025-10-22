import { Page } from "@/types/interfaces";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

export function useAuth() {
    const page = usePage<Page>();
    const user = computed(() => page.props.auth.user);

    return { user };
}
