syntax on
set incsearch
set number
set autoindent

" color scheme
let g:molokai_original = 1
let g:rehash256 = 1
colorscheme molokai

" pathogen
execute pathogen#infect()
filetype plugin indent on


let g:airline_powerline_fonts = 1
if !exists('g:airline_symbols')
	let g:airline_symbols = {}
endif



nmap <F4> :NERDTree<CR>   "设置快捷键  
nmap <F5> :Helptags<CR>
