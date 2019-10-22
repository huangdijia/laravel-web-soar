<template>
    <main :class="['layout', { 'layout-columns': needsColumnLayout }]" :style="gridStyle">
        <soar-input v-model="input" :path="path" @execute="handleExecute"></soar-input>
        <hr ref="gutter" class="layout-gutter" />
        <soar-output :value="output"></soar-output>
    </main>
</template>

<script>
import SoarInput from './SoarInput';
import SoarOutput from './SoarOutput';
import Split from 'split-grid';

export default {
    components: {
        SoarInput,
        SoarOutput,
    },

    props: ['path'],

    data: () => ({
        windowWidth: window.innerWidth,
        gutterWidth: 9,
        minSize: 100,
        breakpoint: 768,
        input: '',
        output: '<span class="text-dimmed">// Use cmd+enter or ctrl+enter to run.</span>',
    }),

    computed: {
        columnPercentage() {
            return ((1 - this.gutterWidth / window.innerWidth) / 2) * 100 + '%';
        },

        rowPercentage() {
            return ((1 - this.gutterWidth / window.innerHeight) / 2) * 100 + '%';
        },

        needsColumnLayout() {
            return this.windowWidth > this.breakpoint;
        },

        gridStyle() {
            if (this.needsColumnLayout) {
                return {
                    gridTemplateColumns: `${this.columnPercentage} ${this.gutterWidth}px ${this.columnPercentage}`,
                };
            }

            return {
                gridTemplateRows: `${this.rowPercentage} ${this.gutterWidth}px ${this.rowPercentage}`,
            };
        },
    },

    methods: {
        handleExecute(output) {
            this.output = output;
        },

        initSplit() {
            this.destroySplit();

            this.split = Split({
                [this.needsColumnLayout ? 'columnGutters' : 'rowGutters']: [
                    {
                        track: 1,
                        element: this.$refs.gutter,
                    },
                ],
                minSize: this.minSize,
            });
        },

        destroySplit() {
            if (this.split) {
                this.split.destroy();
            }
        },
    },

    mounted() {
        this.initSplit();

        this.$watch('needsColumnLayout', this.initSplit);

        window.addEventListener('resize', () => {
            this.windowWidth = window.innerWidth;
        });
    },
};
</script>
