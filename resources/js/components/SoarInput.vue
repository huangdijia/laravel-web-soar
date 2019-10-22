<template>
    <section class="input"><textarea ref="codeEditor" /></section>
</template>

<script>

import "codemirror/theme/ambiance.css";
import "codemirror/lib/codemirror.css";
import "codemirror/addon/hint/show-hint.css";

let CodeMirror = require("codemirror/lib/codemirror");
require("codemirror/addon/edit/matchbrackets");
require("codemirror/addon/selection/active-line");
require("codemirror/mode/sql/sql");
require("codemirror/addon/hint/show-hint");
require("codemirror/addon/hint/sql-hint");

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
                'Ctrl': 'autocomplete'
            },
            // smartIndent: true,
            indentWithTabs: true,
            lineNumbers: true,
            lineWrapping: true,
            mode: 'text/x-sql',
            tabSize: 4,
            // theme: 'ambiance',
            hintOptions: { //自定义提示选项
              tables: {
                users: ['name', 'score', 'birthDate'],
                countries: ['name', 'population', 'size']
              }
            }
        };

        this.codeEditor = CodeMirror.fromTextArea(this.$refs.codeEditor, config);

        this.codeEditor.on('cursorActivity', editor => function () {
          editor.showHint();
        });

        this.codeEditor.on('change', editor => {
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

<style src="codemirror/lib/codemirror.css" /> <style src="codemirror/theme/idea.css" />
