import { defineStore } from 'pinia';

export let usePostStore = defineStore('posts', {
    state() {
        return {
            value: [],
            propName: '',
            notifications: []
        }
    },

    actions: {
        addCommentToPost(comment) {
            this.value.filter(p => p.id == comment.post_id)[0].comments.unshift(comment);
        },
        addCommentResponse(comment) {
            this.value.filter(p => p.id == comment.post_comment.post_id)[0].comments.filter(c => c.id == comment.post_comment_id)[0].responses.unshift(comment);
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
        },
        setNotifications(notifications) {
            this.notifications = notifications;
        },
        addNotification(notification) {
            this.notifications.unshift(notification)
        },
        getNotifications() {
            return this.notifications;
        },
        isNotificationEmpty() {
            return this.notifications.length == 0;
        }

    }

})