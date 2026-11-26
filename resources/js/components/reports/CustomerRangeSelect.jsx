import { useState } from 'react';
import {
    Combobox,
    ComboboxButton,
    ComboboxInput,
    ComboboxOption,
    ComboboxOptions,
} from '@headlessui/react';
import { CheckIcon, ChevronUpDownIcon } from '@heroicons/react/20/solid';

export default function CustomerRangeSelect({
    label,
    placeholder,
    customers = [],
    value,
    onChange,
    disabled = false,
}) {
    const [query, setQuery] = useState('');

    const filteredCustomers =
        query === ''
            ? customers
            : customers.filter((customer) => {
                  const text = `${customer.code} ${customer.name ?? ''}`.toLowerCase();
                  return text.includes(query.toLowerCase());
              });

    const handleInputChange = (event) => {
        const typed = event.target.value;
        setQuery(typed);

        if (!typed.trim()) {
            onChange(null);
            return;
        }

        const exactMatch = customers.find(
            (c) => c.code.toLowerCase() === typed.trim().toLowerCase()
        );
        onChange(exactMatch || { id: typed.trim(), code: typed.trim() });
    };

    return (
        <div>
            <label className="mb-1 block text-xs font-medium text-gray-600">{label}</label>
            <Combobox
                value={value}
                onChange={(selected) => {
                    onChange(selected);
                    setQuery('');
                }}
                onClose={() => setQuery('')}
                disabled={disabled}
                by="code"
                immediate
            >
                <div className="relative">
                    <ComboboxInput
                        aria-label={label}
                        placeholder={placeholder}
                        displayValue={(customer) => {
                            if (!customer) return '';
                            return customer.name
                                ? `${customer.code} - ${customer.name}`
                                : customer.code;
                        }}
                        onChange={handleInputChange}
                        className="w-full rounded-lg border border-gray-300 bg-white py-2 pl-3 pr-10 text-sm text-gray-800 focus:border-gray-500 focus:outline-none disabled:bg-gray-50 disabled:text-gray-500"
                    />
                    <ComboboxButton className="absolute inset-y-0 right-0 flex items-center pr-2">
                        <ChevronUpDownIcon className="size-5 text-gray-400" aria-hidden="true" />
                    </ComboboxButton>

                    <ComboboxOptions className="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-lg bg-white py-1 text-sm shadow-lg ring-1 ring-black/5 empty:invisible focus:outline-none">
                        {query.trim() !== '' &&
                            !customers.some(
                                (c) => c.code.toLowerCase() === query.trim().toLowerCase()
                            ) && (
                                <ComboboxOption
                                    value={{ id: query.trim(), code: query.trim() }}
                                    className="cursor-default select-none px-3 py-2 text-gray-700 data-focus:bg-[#6b727d] data-focus:text-white"
                                >
                                    "<span className="font-semibold">{query.trim()}</span>"
                                </ComboboxOption>
                            )}

                        {filteredCustomers.map((customer) => (
                            <ComboboxOption
                                key={customer.id ?? customer.code}
                                value={customer}
                                className="group relative cursor-default select-none py-2 pl-9 pr-4 text-gray-900 data-focus:bg-[#6b727d] data-focus:text-white"
                            >
                                <span className="block truncate font-normal group-data-[selected]:font-semibold">
                                    {customer.code} {customer.name ? `- ${customer.name}` : ''}
                                </span>
                                <span className="absolute inset-y-0 left-0 hidden items-center pl-2.5 text-blue-600 group-data-[selected]:flex group-data-[focus]:text-white">
                                    <CheckIcon className="size-4" aria-hidden="true" />
                                </span>
                            </ComboboxOption>
                        ))}
                    </ComboboxOptions>
                </div>
            </Combobox>
        </div>
    );
}
