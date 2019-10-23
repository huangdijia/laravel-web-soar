<template>
    <section class="input"><textarea ref="codeEditor" /></section>
</template>

<script>

import "codemirror/addon/hint/show-hint.css";
import 'codemirror/mode/sql/sql';
import "codemirror/addon/hint/show-hint";
import "codemirror/addon/hint/sql-hint";
import CodeMirror from 'codemirror';
import axios from 'axios';


export default {
    data: () => ({
        value: '',
        codeEditor: null,
    }),

    props: ['path'],

    mounted() {
        const config = {
            autofocus: true,
            extraKeys: {
                'Cmd-Enter': () => {
                    this.executeCode();
                },
                'Ctrl-Enter': () => {
                    this.executeCode();
                },
                "F2": "autocomplete"
            },
            smartIndent: true,
            indentWithTabs: true,
            matchBrackets: true,
            lineNumbers: true,
            lineWrapping: true,
            mode: "text/x-mysql",
            tabSize: 4,
            theme: 'tinker',
            hintOptions: { //自定义提示选项
              "completeSingle": false,
              "completeOnSingleClick": true,
              "async": true,
              tables: window.TABLES || {},
            },
            lint: {
                "getAnnotations": CodeMirror.sqlLint,
                "async": true,
                "lintOptions": {}
            }
        };

        this.codeEditor = CodeMirror.fromTextArea(this.$refs.codeEditor, config);

        // this.codeEditor.on('cursorActivity', editor => {
        //     editor.showHint();
        // });

        this.codeEditor.on('change', (editor, change) => {
            if (change.origin !== 'complete' && /\w|\./g.test(change.text[0])) {
                editor.showHint();
            }

            localStorage.setItem('soar-tool', editor.getValue());
        });

        let value = localStorage.getItem('soar-tool');

        if (typeof value === 'string') {
            this.codeEditor.setValue(value);
            this.codeEditor.execCommand('goDocEnd');
        }
    },

    methods: {
        executeCode() {
            let code = this.codeEditor.getValue().trim();

            if (code === '') {
                this.$emit('execute', '<error>You must type some code to execute.</error>');

                return;
            }

            axios.post(this.path, { code }).then(({ data }) => {
                this.$emit('execute', data);
            });
        },
    },
};
</script>

<style src="codemirror/lib/codemirror.css" />
<style src="codemirror/theme/idea.css" />
