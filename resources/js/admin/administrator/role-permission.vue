<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';

import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import Checkbox from '@/components/Forms/Checkbox.vue';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import type { BreadcrumbItem, Admin, NavItem } from '@/types';

interface Props {
  status?: string;
  method?: string;
  role:any,
  administrator: Admin;
  roles: Array<{ id: any; name: string; permissions: any[] }>; // per role ada permission array id
  permissions: Array<{ id: any; name: string }>;
}
const props = defineProps<Props>();

const grouped = props.permissions.reduce((acc, perm) => {
  const words = perm.name.split(' ');
  const key = words.slice(1).join(' '); // ambil semua kata setelah kata pertama
  if (!acc[key]) acc[key] = [];
  acc[key].push(perm);
  return acc;
}, {});



const breadcrumbs: BreadcrumbItem[] = [
  { title: 'User settings', href: '/backend/settings/profile' },
];

// Selected role ID, default dari administrator roles (ambil satu saja, misal yang pertama)
const selectedRole = ref<any | null>(props.role ? props.role?.id : null);
// Form permissions, array id permission yang dicentang
const form = useForm({
  permissions: [] as any[],
  role: props.role?.name,
});

// Set default permissions dari role yang dipilih (jika ada)
const setPermissionsFromRole = (roleId: any | null) => {
  if (!roleId) {
    form.permissions = [];
    return;
  }
  const role = props.roles.find(r => r.id == roleId);
  form.permissions = role ? [...role.permissions.map(r => r.id)] : [];
};

// Inisialisasi permissions sesuai selectedRole
setPermissionsFromRole(selectedRole.value);

// Flag untuk deteksi apakah permissions diubah manual
const permissionsChangedManually = ref(false);

const changeRole = (event: Event) => {
  const target = event.target as HTMLInputElement;
  selectedRole.value = target.value;
  permissionsChangedManually.value = false; // reset flag manual edit
  setPermissionsFromRole(selectedRole.value); // update form.permissions sesuai role
};

const arraysHaveSameElements = (arr1 : Array<any>, arr2 : Array<any>) =>  {
  if (arr1.length !== arr2.length) return false;
  const sorted1 = [...arr1].sort();
  const sorted2 = [...arr2].sort();
  const res = sorted1.every((val, index) => val == sorted2[index]);
  return res;
}
// Watch form.permissions untuk tandai perubahan manual
watch(() => form.permissions.slice(), (newPermissions) => {
  if (selectedRole.value) {
    const role = props.roles.find(r => r.id == selectedRole.value);
    const rolePerms = role ? role.permissions.map(r => r.id) : [];
    permissionsChangedManually.value = !arraysHaveSameElements(newPermissions, rolePerms );
    form.role  = role?.name ?? '';
  } else {
    permissionsChangedManually.value = newPermissions.length > 0;
  }
});

const toggleGroupPermissions = (group: any[], checked: boolean) => {
  const groupIds = group.map(p => p.id);
  if (checked) {
    // Tambah semua ID jika belum ada
    form.permissions = [...new Set([...form.permissions, ...groupIds])];
  } else {
    // Hapus semua ID dari group
    form.permissions = form.permissions.filter(id => !groupIds.includes(id));
  }
};

const isGroupChecked = (group: any[]) => {
  const groupIds = group.map(p => p.id);
  return groupIds.every(id => form.permissions.includes(id));
};

const submit = () => {
    if (!form.role.trim()) {
        // Bisa tambahkan validasi sederhana, misal alert atau set error
        alert('Please enter a role name to create a new role.');
        return;
    }

    form.post(route('administrator.assignRolePermission',{administrator:props.administrator.id}), {
        preserveScroll: true,
    });
};


const navItems: NavItem[] = [
  {
    title: 'Profile',
    href: props.administrator.id ? `/backend/administrator/${props.administrator.id}/edit` : '/backend/administrator/create',
    type: '',
  },
  {
    title: 'Password',
    href: `/backend/administrator/${props.administrator.id}/password`,
    type: '',
  },
  {
    title: 'Roles & Permissions',
    href: `/backend/administrator/${props.administrator.id}/role-permissions`,
    type: '',
  },
];
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Administrator settings" />

    <SettingsLayout title="Administrator settings" description="Manage administrator profile and account settings." :navItems="navItems">
      <div class="flex flex-col space-y-6">
        <HeadingSmall title="Administrator Roles & Permissions" description="Update administrator roles and permissions" />

        <form @submit.prevent="submit" class="space-y-6">
          <!-- Role (radio) -->
          <div class="grid gap-2">
            <Label for="roles" class="font-bold">Role</Label>
            <div class="grid gap-2 grid-cols-3 mt-2">
                <div v-for="role in props.roles" :key="role.id" class="flex items-center">
                <input
                    type="radio"
                    class="block w-fit"
                    v-model="selectedRole"
                    :value="role.id"
                    @change="changeRole($event)"
                    autocomplete="roles"
                    :checked="role.name == form.role"
                    name="role"
                />
                <span class="ml-2 text-sm">{{ role.name }}</span>
                </div>
            </div>
                        <!-- Role name input untuk bikin role baru -->
            <div v-if="permissionsChangedManually" class="mt-2">
              <Label for="createOrUpdate" class="text-sm">Role Name</Label> <small>Please enter a different role name to create a new role.</small>
              <Input id="name" class="mt-2 block w-full" v-model="form.role" required autocomplete="role" placeholder="Role name" />
              <InputError class="mt-2" :message="form.errors.role" />
            </div>
          </div>
          

          <!-- Permissions (checkbox) -->
          <div class="grid gap-2">
            <Label for="permissions" class="font-bold">Permissions</Label>
            <div class="grid gap-5 grid-cols-4 mt-2">
              <div v-for="(group,i) in grouped" :key="i" >
                <div class="border-b pb-2 flex items-center">

                  <input
                    type="checkbox"
                    :checked="isGroupChecked(group)"
                    @change="e => toggleGroupPermissions(group, e.target.checked)"
                    class="appearance-none w-[5px] !p-2 h-[5px] border-0 checked:bg-amber-600 checked:border-transparent focus:outline-none focus:ring-1 focus:ring-amber-500"
                  />
                  <Label for="permissions" class="text-gray-600 ml-2">{{i}}</Label>
                </div>
                <div >
                    <div v-for="permission in group" :key="permission.id" class="py-2 flex items-center">
                        <Checkbox
                        type="checkbox"
                        :value="permission.id"
                        v-model="form.permissions"
                        autocomplete="permissions"
                        class="w-fit h-fit"
                        />
                        <span class="ml-2 text-xs text-gray-600">{{ permission.name.split(' ')[0] }}</span>
                    </div>
                </div>
              </div>
            </div>
            <InputError class="mt-2" :message="form.errors.permissions" />
          </div>

          <div class="flex items-center gap-4">
            <Button :disabled="form.processing">Save</Button>

            <Transition
              enter-active-class="transition ease-in-out"
              enter-from-class="opacity-0"
              leave-active-class="transition ease-in-out"
              leave-to-class="opacity-0"
            >
              <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
            </Transition>
          </div>
        </form>
      </div>
    </SettingsLayout>
  </AppLayout>
</template>
