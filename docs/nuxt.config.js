import theme from '@nuxt/content-theme-docs'

export default theme({
  docs: {
    primaryColor: '#E24F55'
  },
  content: {
    markdown: {
      remarkPlugins: ['remark-emoji']
      }
  }
})
