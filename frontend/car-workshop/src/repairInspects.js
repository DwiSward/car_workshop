import * as React from "react";
import { 
    List, 
    Datagrid, 
    TextField, 
    EmailField, 
    Edit,
    SimpleForm,
    TextInput,
    Create,
    ReferenceInput,
    SelectInput,
    ArrayInput, 
    SimpleFormIterator,
    Form,
    ArrayField,
    SaveButton,
    NumberInput,
    SelectField
} from 'react-admin';

var link = window.location.href
var id = link.substring(link.lastIndexOf('/') + 1)
export const RepairInspectList = () => (
    <List>
        <Datagrid rowClick="edit">
            <TextField source="owner_name" />
            <TextField source="created_at_text" />
            <TextField source="status_of_services" />
            <TextField source="status_text" />
        </Datagrid>
    </List>
);

export const RepairInspectEdit = (prop) => (
    <Edit>
        <Form>
            <TextInput source="owner_name" editable="false" />
            <TextInput source="car_brand" editable="false" />
            <TextInput source="work_duration" editable="false" />
            <TextInput source="total" editable="false" />
            <SelectInput source="status" choices={[
                { id: 3, name: 'On Proses' },
                { id: 4, name: 'Done' }
            ]} />
            <br />
            Select status done if no complaine. 
            <br />
            <ArrayField source="repair_services">
                <Datagrid>
                    <TextField source="service_name" />
                    <TextField source="price" />
                </Datagrid>
            </ArrayField>
            <ArrayInput source="repairServices">
                <SimpleFormIterator>
                    <ReferenceInput 
                        source="repair_service_id" 
                        reference="repair-services"
                        filter={{ repair_id: id }}>
                        <SelectInput optionText="service_name" />
                    </ReferenceInput>
                    <TextInput source="note" />
                </SimpleFormIterator>
            </ArrayInput>
            <SaveButton />
        </Form>
    </Edit>
);

export const RepairInspectCreate = () => (
    <Create>
        <SimpleForm>
            <ReferenceInput source="car_id" reference="cars">
               <SelectInput optionText="brand" />
            </ReferenceInput>
            <TextInput source="work_duration" />
            <ArrayInput source="repairServices">
                <SimpleFormIterator>
                    <ReferenceInput source="service_id" reference="services">
                        <SelectInput optionText="name" />
                    </ReferenceInput>
                    <NumberInput source="qty" />
                    <TextInput source="note" />
                </SimpleFormIterator>
            </ArrayInput>
        </SimpleForm>
    </Create>
);