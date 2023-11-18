import { ref, watch } from "vue"
import { router, usePage } from "@inertiajs/vue3";
import useIntersect from './useIntersect';
import { usePostStore } from '../useStore/usePostStore'


export default function infiniteScroll(propName, landmark = null, margin = '0px 0px 50px 0px', only = []) {

    const posts = usePostStore();
    var value = usePage().props[propName];
    if (propName == 'posts')
        posts.setPosts(value.data);
    if (propName == 'comments' && value)
        posts.setComments(value.data);
    const initialUrl = usePage().url;

    watch(() => usePage().props[propName], newValue => {
        if (propName == 'posts' && usePage().props['savePosts']) {
            console.log('Success');
            return;
        }
        value = newValue;
    })

    const loadMoreData = () => {
        if (value && !value.next_page_url)
            return;
        router.get(value.next_page_url, {}, {
            preserveScroll: true,
            preserveState: true,
            replace: false,
            only: only,
            replace: true,
            onFinish: () => {
                if (propName == 'posts')
                    // window.history.replaceState({}, '', '/');
                    router.replace('/');
                else
                    window.history.replaceState({}, '', initialUrl);
                if (propName == 'posts')
                    posts.addPosts(value?.data);
                if (propName == 'comments')
                    posts.addComments(value?.data);
            }
        });
        // axios.get(value.next_page_url).then(response => {
        //     console.log(response);
        // })
    }

    if (landmark)
        useIntersect(landmark, loadMoreData, margin);

    return { loadMoreData };
}