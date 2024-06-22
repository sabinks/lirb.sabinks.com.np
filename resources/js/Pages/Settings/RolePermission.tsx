import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import { useEffect, useState } from 'react';
import { classNames } from '@/utils';
import ListBox from '@/Components/listBox';
import axios from 'axios';
type SelectedRoleType = {
    id: number;
    name: string;
};
function RolePermission({ auth, roles, permissions, permissions_categories, role_permissions }: any) {
    const page = usePage()
    const [rolePer, setRolePer] = useState<any>([])
    const [rolePermissions, setRolePermissions] = useState(false);
    const { get } = useForm()
    const [menu_order, setMenuOrder] = useState<any>(['menu', 'list', 'create', 'show', 'update', 'delete', 'other'])
    const [selectedRole, setSelectedRole] = useState<SelectedRoleType>({
        id: 0,
        name: "",
    });
    useEffect(() => {
        if (roles) {
            setSelectedRole(roles[0]);
        }
    }, [roles]);
    useEffect(() => {
        if (selectedRole?.id != 0) {
            handleRoleHasPermissions()
        }
    }, [selectedRole]);
    const handleRoleHasPermissions = async () => {
        axios.get(`role-has-permissions/${selectedRole.id}`).then((res: any) => setRolePer(res.data))
    }
    const handlePermissionChange = async (e: any, permission_id: number) => {
        const { checked } = e.target;
        const role_id = selectedRole?.id ? selectedRole.id : 0;
        const res = await setPermissionForRole(
            permission_id,
            role_id,
            checked
        );
        const roleHasPermissions = await handleRoleHasPermissions()
        setRolePer(roleHasPermissions)
    };
    const setPermissionForRole = async (permission_id: number, role_id: number, checked: boolean) => {
        if (checked) {
            const response = await axios.post("/assign-permission", {
                role_id, permission_id
            })
            return response.data;
        } else {
            const response = await axios.post("/revoke-permission", {
                role_id, permission_id
            })
            return response.data;
        }
    };
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Role/Permissions</h2>}
        >
            <Head title="Role/Permissions" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <h1>Role/Perissions</h1>
                        <div className="">
                            <h2 className="font-semibold">Roles</h2>
                            {
                                roles && (
                                    <div className="flex">
                                        <ListBox
                                            selected={selectedRole}
                                            setSelected={setSelectedRole}
                                            people={roles}
                                        />
                                    </div>
                                )
                            }
                        </div>
                        <div className="grid grid-cols-1">
                            <div className="">
                                {
                                    permissions_categories?.map(
                                        (category_name: any, index: number) => {
                                            const data = permissions.filter((permission: any) => permission.category_name == category_name)

                                            const newItem = menu_order.map((item: any) => data.some((i: any) => i.method_name == item) ? data.filter((i: any) => i.method_name == item)[0] : '')

                                            return (
                                                <div key={index} className={classNames(index % 2 == 0 ? 'bg-gray-50' : 'bg-white')}>
                                                    <div className='border-b py-3 border-primary1'>
                                                        <div className='flex justify-between'>
                                                            <div className='flex justify-center items-center '>
                                                                <h3 className='block font-medium text-gray-700 pl-2'>
                                                                    <span className=''>{category_name} </span>
                                                                </h3>
                                                            </div>
                                                            <div className='flex'>
                                                                {
                                                                    newItem
                                                                        .map((permission: any, index1: number) => {
                                                                            return (
                                                                                permission ? <div key={index1} className="w-16 md:w-16 lg:w-24 xl:w-24 2xl:w-36">

                                                                                    <input
                                                                                        type='checkbox'
                                                                                        className='h-4 w-4 text-secondary focus:ring-cerulean-300 focus:ring-3 border-gray-300 rounded-xl'
                                                                                        checked={
                                                                                            rolePer?.length && rolePer?.some((x: any) => x.id == permission.id) ? true : false
                                                                                        }
                                                                                        onChange={(e) => {
                                                                                            setRolePermissions(true);
                                                                                            handlePermissionChange(e, permission.id);
                                                                                        }}
                                                                                    />
                                                                                    <span className={`ml-1 text-sm font-semibold text-gray-700`}>
                                                                                        {permission.method_name}
                                                                                    </span>
                                                                                </div>
                                                                                    : <div key={index1} className="w-16 md:w-16 lg:w-24 xl:w-24 2xl:w-36">
                                                                                    </div>
                                                                            );
                                                                        })}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            );
                                        }
                                    )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    )
}

export default RolePermission