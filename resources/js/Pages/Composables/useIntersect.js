import { onMounted, onUnmounted } from 'vue';


export default function useIntersect(ref, callback, margin = '0px 0px 10px 0') {

    onMounted(() => {
        observer.observe(ref.value);
    })

    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting)
                callback();
        })
    }, {
        rootMargin: margin
    })

    onUnmounted(() => {
        observer.disconnect();
    })

}