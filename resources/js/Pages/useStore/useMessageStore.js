import { defineStore } from 'pinia';

export let useMessageStore = defineStore('notifications', {
    state() {
        return {
            messages: [],
            unreadNotifications: 0,
            notifications: [],
            unreadGroupNotifications: 0,
            groupNotifications: [],
        }
    },

    actions: {
        addNotification(notification) {
            this.notifications.push(notification);
        },
        addGroupNotification(notification) {
            this.groupNotifications.push(notification);
        },
        increaseUnreadNotifications() {
            this.unreadNotifications += 1;
        },
        updateUnreadNotifications() {
            this.unreadNotifications = this.notifications.length + this.groupNotifications.length;
        },
        setNotifications(notifications) {
            this.notifications = [...notifications];
        },
        setGroupNotifications(notifications) {
            this.groupNotifications = [...notifications];
        },
        removeNotificationsForUser(userId) {
            this.notifications = this.notifications.filter(notification => notification.hasOwnProperty('data') ? notification.data.sender !== userId : notification.sender !== userId);
        },
        removeGroupNotifications(groupId) {
            this.groupNotifications = this.groupNotifications.filter(notification => notification.hasOwnProperty('data') ? notification.data.group_id !== groupId : notification.group_id !== groupId);
        },
        setMessages(messages) {
            this.messages = [...messages];
        },
        addMessage(message) {
            this.messages.push(message);
        },
        getUnreadNotificationsForUser(userId) {
            return this.notifications.filter(notification => notification.hasOwnProperty('data') ? notification.data.sender === userId : notification.sender === userId).length;
        },
        getUnreadGroupNotifications(groupId) {
            return this.groupNotifications.filter(notification => notification.hasOwnProperty('data') ? notification.data.group_id === groupId : notification.group_id === groupId).length;
        },
        getNotifications() {
            return this.notifications;
        },
        getGroupNotifications() {
            return this.groupNotifications;
        },
        getMessages() {
            return this.messages;
        }
    }

})