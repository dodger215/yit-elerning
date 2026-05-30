const fs = require('fs');
const path = require('path');

const dir = '/home/ked/Documents/YIT-elearning/resources/js/Components/Public';
const files = fs.readdirSync(dir).filter(f => f.endsWith('.vue')).map(f => path.join(dir, f));

files.forEach(file => {
    let content = fs.readFileSync(file, 'utf8');
    
    // Fix class={...} to :class="..."
    content = content.replace(/class=\{([^}]+)\}/g, ':class="$1"');
    
    // Fix style={{...}} to :style="{...}"
    content = content.replace(/style=\{\{([^}]+)\}\}/g, ':style="{$1}"');
    
    // Fix key={...} to :key="..."
    content = content.replace(/key=\{([^}]+)\}/g, ':key="$1"');
    
    // Fix onError={...} to @error="..."
    content = content.replace(/onError=\{\([^)]*\)\s*=>\s*\{([^}]+)\}\}/g, '@error="$1"');
    content = content.replace(/onError=\{([^}]+)\}/g, '@error="$1"');
    
    // Fix onClick={...} to @click="..."
    content = content.replace(/onClick=\{\([^)]*\)\s*=>\s*\{([^}]+)\}\}/g, '@click="$1"');
    content = content.replace(/onClick=\{([^}]+)\}/g, '@click="$1"');
    
    content = content.replace(/ src=\{([^}]+)\}/g, ' :src="$1"');
    content = content.replace(/ href=\{([^}]+)\}/g, ' :href="$1"');
    content = content.replace(/ alt=\{([^}]+)\}/g, ' :alt="$1"');
    
    // Remove {features.map...} opening and closing
    content = content.replace(/\{([a-zA-Z0-9_]+)\.map\(\([^)]+\)\s*=>\s*\(/g, '<!-- Vue v-for needed here for $1 -->');
    content = content.replace(/\)\)\}/g, '<!-- End v-for -->');
    
    content = content.replace(/=""/g, ''); 
    content = content.replace(/icon=\{<([^>]+)>\} /g, 'icon="$1" ');
    
    // Handle specific JSX comments remaining
    content = content.replace(/\{\/\*/g, '<!--');
    content = content.replace(/\*\/\}/g, '-->');

    fs.writeFileSync(file, content);
});
