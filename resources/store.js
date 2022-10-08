export default {
  state: {
    domains: [],
    domainPaginator: {}
  },
  mutations: {},
  getters: {
    domains: state => state.domains,
    domainPaginator: state => state.domainPaginator,
  },
  actions: {

    async getDomains({ state }, { page, limit } = { page: 1, limit: 15 }) {
      const { data: { data, ...pagination } } = await axios.get(buildUrl('/api/basement/domains/namecheap', { page, limit }));
      
      state.domains = data;
      state.domainPaginator = pagination;
    },
    async updateDomain({ state }, domain) {
        const { data } = await axios.put(`/api/basement/domains/namecheap/${domain.id}`, domain);

        state.domains = state.domains.map(p => p.id === data.id ? data : p);
    },
  },
}
