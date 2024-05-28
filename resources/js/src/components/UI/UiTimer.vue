<template>
    <div class="timer">
        <p>{{ timeLeft }}</p>
    </div>
</template>

<script>
export default {
    name: "ui-timer",
    data() {
        return {
            totalTimeInSeconds: 0,
            timerInterval: null,
            timeLeft: "00:00",
        };
    },
    props: {
        seconds: {
            type: Number,
            required: true,
        },
    },
    methods: {
        updateTimer() {
            const minutes = Math.floor(this.totalTimeInSeconds / 60);
            const seconds = this.totalTimeInSeconds % 60;
            const displayMinutes = String(minutes).padStart(2, "0");
            const displaySeconds = String(seconds).padStart(2, "0");
            this.timeLeft = `${displayMinutes}:${displaySeconds}`;
            if (this.totalTimeInSeconds > 0) {
                this.totalTimeInSeconds--;
            } else {
                clearInterval(this.timerInterval);
                this.$emit("end-time");
            }
        },
    },
    mounted() {
        this.totalTimeInSeconds = this.seconds;
        this.timerInterval = setInterval(this.updateTimer, 1000);
    },
};
</script>

<style scoped lang="scss">
.timer {
    display: block;
    font-size: 20px;
    text-align: center;
    font-weight: 600;
    color: var(--surface-900);
    background-color: var(--surface-hover);
    border-radius: 10px;
    padding: 0 10px
}
</style>
