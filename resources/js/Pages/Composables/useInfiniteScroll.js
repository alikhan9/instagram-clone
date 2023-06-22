import { ref, watch } from "vue"
import { router, usePage } from "@inertiajs/vue3";
import useIntersect from './useIntersect';


export default function infiniteScroll(propName, landmark = null, margin = '0px 0px 50px 0px') {

    var value = usePage().props[propName];
    const data = ref(value.data);
    const initialUrl = usePage().url;

    watch(() => usePage().props[propName], newValue => {
        value = newValue;
    })

    const loadMoreData = () => {
        if (!value.next_page_url)
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
        useIntersect(landmark, loadMoreData, margin);

    return { data, loadMoreData };
}