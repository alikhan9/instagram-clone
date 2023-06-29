import { ref, watch } from "vue"
import { router, usePage } from "@inertiajs/vue3";
import useIntersect from './useIntersect';
import { usePostStore } from '../useStore/usePostStore'


export default function infiniteScroll(propName, landmark = null, margin = '0px 0px 50px 0px') {

    const posts = usePostStore();
    var value = usePage().props[propName];
    posts.setPosts(value.data);
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
                posts.addPost(...value.data);
            }
        });
    }

    if (landmark)
        useIntersect(landmark, loadMoreData, margin);

    return { loadMoreData };
}