<template>
    <section class="output">
        <!-- <pre><code v-html="value"></code></pre> -->
        <code v-html="value"></code>
    </section>
</template>

<script>
export default {
    props: ['value'],
};
</script>
