import { computed, ref, watch } from "vue"
import { router, usePage } from "@inertiajs/vue3";
import useIntersect from './useIntersect';


export default function infiniteScroll(propName, landmark = null) {

    var value = usePage().props[propName];
    const data = ref(value.data);
    const initialUrl = usePage().url;
    const canLoadMore = computed(() => value.next_page_url !== null)

    watch(() => usePage().props[propName], newValue => {
        value = newValue;
    })

    const loadMoreData = () => {
        if (!canLoadMore.value)
            return;
        router.get(value.next_page_url, {}, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                window.history.replaceState({}, '', initialUrl);
                data.value.push(...value.data);
            }
        });
    }

    if (landmark)
        useIntersect(landmark, loadMoreData, '0px 0px 50px 0px');


    return { data, loadMoreData };
}