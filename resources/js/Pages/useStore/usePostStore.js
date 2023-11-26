import { defineStore } from 'pinia';

export let usePostStore = defineStore('posts', {
    state() {
        return {
            value: [],
            propName: '',
            notifications: [],
            comments: [],
        }
    },

    actions: {
        addComment(comment) {
            this.comments.push(comment);
        },
        addComments(comments) {
            this.comments = [...this.comments, ...comments];
        },
        addCommentResponse(comment) {
            this.comments.filter(c => c.id == comment.post_comment_id)[0].responses.push(comment);
        },
        addLikeToComment(like) {
            this.comments.filter(c => c.id == like.post_comment_id)[0].likes.push(like.post_comment_id);
        },
        addLikeToResponse(like, comment_id) {
            this.comments.filter(c => c.id == comment_id)[0].responses.filter(r => r.id == like.comment_response_id)[0].likes.push(like.comment_response_id);
        },
        removeLikeFromComment(like) {
            this.comments.filter(c => c.id == like.post_comment_id)[0].likes.splice(0, 1);
        },
        removeLikeFromResponse(like, comment_id) {
            this.comments.filter(c => c.id == comment_id)[0].responses.filter(r => r.id == like.comment_response_id)[0].likes.splice(0, 1);
        },
        setPosts(posts) {
            this.value = [...posts];
        },
        setComments(comments) {
            this.comments = [...comments];
        },
        addPosts(posts) {
            this.value = [...this.value, ...posts]
        },
        addComments(comments) {
            this.comments = [...this.comments, ...comments]
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