import * as React from "react";
import authProvider from './authProvider';
import { fetchUtils, Admin, Resource } from 'react-admin';
import { CustomerList, CustomerEdit, CustomerCreate } from './customers';
import { RepairList, RepairEdit, RepairCreate } from './repairs';
// import jsonServerProvider from 'ra-data-json-server';
import simpleRestProvider from 'ra-data-simple-rest';

const httpClient = (url, options = {}) => {
  if (!options.headers) {
      options.headers = new Headers({ Accept: 'application/json' });
  }
  const { token } = JSON.parse(localStorage.getItem('auth'));
  options.headers.set('Authorization', `Bearer ${token}`);
  return fetchUtils.fetchJson(url, options);
};

const dataProvider = simpleRestProvider('http://localhost:8000/api/admin', httpClient);

const App = () => (
  <Admin authProvider={authProvider} dataProvider={dataProvider}>
    <Resource name="customers" list={CustomerList} edit={CustomerEdit} create={CustomerCreate} />
    <Resource name="repairs" list={RepairList} edit={RepairEdit} create={RepairCreate} />
    {/* <Resource name="users" list={ListGuesser} /> */}
  </Admin>
);

export default App;