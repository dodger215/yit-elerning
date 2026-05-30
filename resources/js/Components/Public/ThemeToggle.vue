<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
<Tooltip title={`Switch to ${isDark ? 'light' : 'dark'} mode`}>
            <button
                type="text"
                class={className}
                icon={isDark ? <BulbOutlined /> : <BulbFilled />}
                onClick={toggleTheme}
                style={{
                    fontSize: '20px',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    // color: isDark ? '#A0AEC0' : '#4A5568', // Let CSS handle color if className provided
                }}
                aria-label={`Switch to ${isDark ? 'light' : 'dark'} mode`}
            />
        </Tooltip>
</template>

<style scoped>

</style>
