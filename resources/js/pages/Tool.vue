<template>
  <div class="space-y-4">
    <div class="md:flex md:items-center space-y-2 md:space-y-0">
      <Heading>{{ __('Log Viewer') }}</Heading>

      <div
        class="flex flex-col md:flex-row items-start md:items-center ml-auto space-y-2 md:space-y-0 md:space-x-2"
      >
        <div class="flex items-center">
          <SelectControl
            v-if="logs.length > 1"
            v-model="selectedLogFile"
            @selected="handleLogChange"
            :options="logs"
            size="sm"
          >
            <option value="" selected disabled>
              {{ __('Select a log file...') }}
            </option>
          </SelectControl>
          <p v-else-if="logs.length == 1" class="font-bold truncate">
            {{ selectedLogFile }}
          </p>
          <p v-else>
            &mdash;
          </p>
        </div>

        <div
          class="md:ml-2 inline-flex items-center shadow rounded-lg bg-white dark:bg-gray-800 px-2 h-8"
        >
          <ToolbarButton
            @click="replaceContent"
            icon-name="refresh"
            v-tooltip="__('Refresh')"
          />

          <ToolbarButton
            @click="playing = !playing"
            :class="{
              'text-green-500': playing,
              'text-gray-500': !playing,
            }"
            icon-name="clock"
            class="w-8 h-8"
            v-tooltip="playing ? __('Stop polling') : __('Start polling')"
          />

          <ToolbarButton
            @click="scrollToTop"
            icon-name="arrow-up"
            v-tooltip="__('Scroll to top')"
          />

          <ToolbarButton
            @click="scrollToBottom"
            icon-name="arrow-down"
            v-tooltip="__('Scroll to bottom')"
          />
        </div>
      </div>
    </div>

    <Card class="bg-gray-900 text-gray-300 overflow-hidden">
      <textarea ref="viewer" />
    </Card>

    <p class="text-xs uppercase font-bold tracking-widest">
      {{ __(`:number ${lineLabel}`, { number: numberOfLines }) }}
    </p>
  </div>
</template>

<script>
import CodeMirror from 'codemirror'
import ToolbarButton from '../components/ToolbarButton.vue'
import { singularOrPlural } from 'laravel-nova-util'
import isNil from 'lodash/isNil'

export default {
  codemirror: null,

  components: {
    ToolbarButton,
  },

  props: {
    logs: {
      type: Array,
      default: [],
    },
  },

  data: () => ({
    playing: false,
    content: null,
    lastLine: 0,
    interval: null,
    selectedLogFile: null,
    numberOfLines: 0,
    scrolledToBottom: false,
  }),

  mounted() {
    this.setupCodeMirror()
    this.selectFirstLog()
    this.replaceContent()
    this.scrollToBottom()
    this.setupInterval()
  },

  beforeUnmount() {
    clearInterval(this.interval)
    this.codemirror = null
  },

  methods: {
    setupCodeMirror() {
      this.codemirror = CodeMirror.fromTextArea(this.$refs.viewer, {
        tabSize: 4,
        indentWithTabs: true,
        lineWrapping: false,
        lineNumbers: true,
        theme: 'dracula',
        readOnly: true,
      })
      this.codemirror?.setSize('100%', 'calc(100vh - 19rem)')

      this.codemirror.on('scroll', this.updateScrollPosition)
    },

    selectFirstLog() {
      this.selectedLogFile = this.logs[0].value
    },

    updateScrollPosition() {
      const scrollInfo = this.codemirror.getScrollInfo()

      this.scrolledToBottom =
        scrollInfo.top >= scrollInfo.height - scrollInfo.clientHeight

      if (!this.scrolledToBottom) {
        this.playing = false
      }
    },

    handleLogChange(option) {
      const wasPlaying = this.playing
      this.playing = false
      this.replaceContent()
      if (wasPlaying) {
        this.playing = true
      }
    },

    requestContent() {
      return Nova.request().get('/nova-vendor/logs', {
        params: { log: this.selectedLogFile, lastLine: this.lastLine },
      })
    },

    fetchContent() {
      if (isNil(this.selectedLogFile)) {
        return
      }

      this.requestContent().then(
        ({ data: { content, lastLine, numberOfLines } }) => {
          this.lastLine = lastLine
          this.content = this.content += content
          this.numberOfLines = numberOfLines
          this.codemirror?.replaceRange(
            content,
            CodeMirror.Pos(this.codemirror?.lastLine() - 1)
          )
        }
      )
    },

    replaceContent() {
      this.lastLine = 0

      if (isNil(this.selectedLogFile)) {
        return
      }

      this.requestContent().then(
        ({ data: { content, lastLine, numberOfLines } }) => {
          this.lastLine = lastLine
          this.content = content
          this.numberOfLines = numberOfLines
          this.codemirror?.getDoc().setValue(this.content)
        }
      )
    },

    scrollToTop() {
      this.$nextTick(() => this.codemirror?.setCursor(0, 0))
    },

    scrollToBottom() {
      this.$nextTick(() => {
        const scrollInfo = this.codemirror.getScrollInfo()
        this.codemirror.scrollTo(0, scrollInfo.height)
      })
    },

    setupInterval() {
      this.interval = setInterval(() => {
        if (this.playing) {
          this.fetchContent()
          this.scrollToBottom()
        }
      }, 1000)
    },
  },

  computed: {
    lineLabel() {
      return singularOrPlural(this.numberOfLines, 'line')
    },
  },
}
</script>
