import AuthenticatedLayout from '@/Layouts/GuestLayout';
import { Head } from '@inertiajs/react';
import { PageProps } from '@/types';

export default function Products({ products }: any) {
    console.log(products);

    return (
        <div className="">
            <Head title="Products" />

            <div className="py-12 text-white">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900 dark:text-gray-100">Products</div>
                        {
                            products?.map((product: any) => (
                                <div className="">
                                    <h1>{product?.name}</h1>
                                </div>
                            ))
                        }
                        {
                            products.length == 0 &&
                            <h1>No products!</h1>
                        }
                    </div>
                </div>
            </div>
        </div>
    );
}
