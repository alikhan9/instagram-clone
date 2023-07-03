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
            this.value.filter(p => p.id == comment.post_id)[0].comments.push(comment);
        },
        addCommentResponse(comment) {
            this.value.filter(p => p.id == comment.post_comment.post_id)[0].comments.filter(c => c.id == comment.post_comment_id)[0].responses.push(comment);
        },
        addLikeToComment(like, post_id) {
            this.value.filter(p => p.id == post_id)[0].comments.filter(c => c.id == like.post_comment_id)[0].likes.push(like.post_comment_id);
        },
        addLikeToResponse(like, post_id, comment_id) {
            this.value.filter(p => p.id == post_id)[0].comments.filter(c => c.id == comment_id)[0].responses.filter(r => r.id == like.comment_response_id)[0].likes.push(like.comment_response_id);
        },
        removeLikeFromComment(like, post_id) {
            this.value.filter(p => p.id == post_id)[0].comments.filter(c => c.id == like.post_comment_id)[0].likes.splice(0, 1);
        },
        removeLikeFromResponse(like, post_id, comment_id) {
            this.value.filter(p => p.id == post_id)[0].comments.filter(c => c.id == comment_id)[0].responses.filter(r => r.id == like.comment_response_id)[0].likes.splice(0, 1);
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