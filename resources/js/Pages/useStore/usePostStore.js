import { defineStore } from 'pinia';

export let usePostStore = defineStore('posts', {
    state() {
        return {
            value: [],
            propName: ''
        }
    },

    actions: {
        addCommentToPost(comment) {
            this.value.filter(p => p.id == comment.post_id)[0].comments.unshift(comment);
        },
        setPosts(posts) {
            this.value = posts;
        },
        addPost(post) {
            this.value.push(post);
        },
        setPropName(name) {
            this.propName = name;
        },
        getValue() {
            return this.value;
        }
    }

})