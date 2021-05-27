Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'nova-language-editor',
      path: '/language-management',
      component: require('./components/Tool'),
    },
  ])
})
