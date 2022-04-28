import * as React from "react";
import authProvider from './authProvider';
import { fetchUtils, Admin, Resource } from 'react-admin';
import { CustomerList, CustomerEdit, CustomerCreate } from './customers';
import { RepairList, RepairEdit, RepairCreate } from './repairs';
import { RepairServiceList, RepairServiceEdit, RepairServiceCreate } from './repairServices';
import { RepairMechanicList, RepairMechanicEdit, RepairMechanicCreate } from './repairMechanics';
import { RepairInspectList, RepairInspectEdit, RepairInspectCreate } from './repairInspects';
import { MechanicList, MechanicEdit, MechanicCreate } from './mechanics';
import { ServiceList, ServiceEdit, ServiceCreate } from './services';
import { CarList, CarEdit, CarCreate } from './cars';
// import jsonServerProvider from 'ra-data-json-server';
import simpleRestProvider from 'ra-data-simple-rest';

const httpClient = (url, options = {}) => {
  if (!options.headers) {
      options.headers = new Headers({ Accept: 'application/json' });
  }
  const { token, permission } = JSON.parse(localStorage.getItem('auth'));
  options.headers.set('Authorization', `Bearer ${token}`);
  return fetchUtils.fetchJson(url, options);
};

const dataProvider = simpleRestProvider('http://localhost:8000/api/admin', httpClient);

const App = () => (
  <Admin authProvider={authProvider} dataProvider={dataProvider}>
    {permission => (
        <>
            {/* Only include the categories resource for admin users */}
            {permission === 'admin' ? <Resource name="customers" list={CustomerList} edit={CustomerEdit} create={CustomerCreate} /> : null}
            {permission === 'admin' ? <Resource name="mechanics" list={MechanicList} edit={MechanicEdit} create={MechanicCreate} /> : null}
            {permission === 'admin' ? <Resource name="services" list={ServiceList} edit={ServiceEdit} create={ServiceCreate} /> : null}
            {permission === 'admin' ? <Resource name="cars" list={CarList} edit={CarEdit} create={CarCreate} /> : null}
            {(permission === 'admin'  || permission === 'user') ? <Resource name="repairs" list={RepairList} edit={RepairEdit} create={RepairCreate} /> : null}
            {permission === 'admin' ? <Resource name="repair-services" list={RepairServiceList} edit={RepairServiceEdit} /> : null}
            {permission === 'admin' ? <Resource name="repair-inspects" list={RepairInspectList} edit={RepairInspectEdit} /> : null}
            {permission === 'mechanic' ? <Resource name="repair-mechanics" list={RepairMechanicList} edit={RepairMechanicEdit} /> : null}
        </>
    )}
    {/* <Resource name="users" list={ListGuesser} /> */}
  </Admin>
);

export default App;