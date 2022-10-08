Spork.setupStore({
    Basement: require("./store").default,
})


Spork.routesFor('Basement', [
  Spork.authenticatedRoute('/basement', require('./Basement/Basement').default, {
    children: [
      Spork.authenticatedRoute('dashboard', require('./Basement/Dashboard').default),
      Spork.authenticatedRoute('settings', require('./Basement/Settings').default),
      Spork.authenticatedRoute('domains', require('./Basement/Domains').default),
      Spork.authenticatedRoute('servers', require('./Basement/Servers').default),    
    ]
  }),
]);
