import { defineStore } from 'pinia';

export let useMessageStore = defineStore('notifications', {
    state() {
        return {
            messages: [],
            unreadNotifications: 0,
            notifications: [],
        }
    },

    actions: {
        addNotification(message) {
            this.notifications.push(message);
        },
        increaseUnreadNotifications() {
            this.unreadNotifications += 1;
        },
        updateUnreadNotifications() {
            this.unreadNotifications = this.notifications.length;
        },
        setNotifications(notifications) {
            this.notifications = [...notifications];
        },
        removeNotificationsForUser(userId) {
            this.notifications = this.notifications.filter(notification => notification.data?.sender !== userId);
        },
        setMessages(messages) {
            this.messages = [...messages];
        },
        addMessage(message) {
            this.messages.push(...message);
        },
        getUnreadNotificationsForUser(userId) {
            return this.notifications.filter(notification => notification.data.sender === userId).length;
        },
        getNotifications() {
            return this.notifications;
        }
    }

})