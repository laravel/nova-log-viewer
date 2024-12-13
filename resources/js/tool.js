import Tool from './pages/Tool'

const Nova = window.Nova

Nova.booting((app, store) => {
  Nova.inertia('NovaLogViewer', Tool)
})
