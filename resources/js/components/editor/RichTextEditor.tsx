import React from 'react';
import { useEditor, EditorContent } from '@tiptap/react';
import StarterKit from '@tiptap/starter-kit';
import TextStyle from '@tiptap/extension-text-style';
import { FontFamily } from '@tiptap/extension-font-family';
import { Bold, Italic, Underline, List, ListOrdered, Quote, Undo, Redo } from 'lucide-react';

interface RichTextEditorProps {
    content?: string;
    onChange?: (html: string) => void;
    placeholder?: string;
    className?: string;
}

const RichTextEditor: React.FC<RichTextEditorProps> = ({
    content = '',
    onChange,
    placeholder = 'Escribe tu contenido aquí...',
    className = ''
}) => {
    const editor = useEditor({
        extensions: [
            StarterKit,
            TextStyle,
            FontFamily,
        ],
        content,
        onUpdate: ({ editor }) => {
            if (onChange) {
                onChange(editor.getHTML());
            }
        },
        editorProps: {
            attributes: {
                class: 'prose prose-sm sm:prose lg:prose-lg xl:prose-2xl mx-auto focus:outline-none dark:prose-invert min-h-[200px] p-4',
            },
        },
    });

    if (!editor) {
        return null;
    }

    const ToolbarButton: React.FC<{
        onClick: () => void;
        isActive?: boolean;
        children: React.ReactNode;
        title: string;
    }> = ({ onClick, isActive, children, title }) => (
        <button
            onClick={onClick}
            className={`p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors ${
                isActive ? 'bg-gray-200 dark:bg-gray-600' : ''
            }`}
            title={title}
            type="button"
        >
            {children}
        </button>
    );

    return (
        <div className={`border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 ${className}`}>
            <div className="border-b border-gray-300 dark:border-gray-600 p-2 flex flex-wrap gap-1">
                <ToolbarButton
                    onClick={() => editor.chain().focus().toggleBold().run()}
                    isActive={editor.isActive('bold')}
                    title="Negrita"
                >
                    <Bold className="h-4 w-4" />
                </ToolbarButton>
                
                <ToolbarButton
                    onClick={() => editor.chain().focus().toggleItalic().run()}
                    isActive={editor.isActive('italic')}
                    title="Cursiva"
                >
                    <Italic className="h-4 w-4" />
                </ToolbarButton>
                
                <ToolbarButton
                    onClick={() => editor.chain().focus().toggleUnderline().run()}
                    isActive={editor.isActive('underline')}
                    title="Subrayado"
                >
                    <Underline className="h-4 w-4" />
                </ToolbarButton>

                <div className="w-px bg-gray-300 dark:bg-gray-600 mx-1"></div>

                <select
                    className="px-2 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                    onChange={(e) => {
                        const level = parseInt(e.target.value);
                        if (level === 0) {
                            editor.chain().focus().setParagraph().run();
                        } else {
                            editor.chain().focus().toggleHeading({ level: level as 1 | 2 | 3 | 4 | 5 | 6 }).run();
                        }
                    }}
                >
                    <option value="0">Párrafo</option>
                    <option value="1">Título 1</option>
                    <option value="2">Título 2</option>
                    <option value="3">Título 3</option>
                </select>

                <div className="w-px bg-gray-300 dark:bg-gray-600 mx-1"></div>

                <ToolbarButton
                    onClick={() => editor.chain().focus().toggleBulletList().run()}
                    isActive={editor.isActive('bulletList')}
                    title="Lista con viñetas"
                >
                    <List className="h-4 w-4" />
                </ToolbarButton>
                
                <ToolbarButton
                    onClick={() => editor.chain().focus().toggleOrderedList().run()}
                    isActive={editor.isActive('orderedList')}
                    title="Lista numerada"
                >
                    <ListOrdered className="h-4 w-4" />
                </ToolbarButton>
                
                <ToolbarButton
                    onClick={() => editor.chain().focus().toggleBlockquote().run()}
                    isActive={editor.isActive('blockquote')}
                    title="Cita"
                >
                    <Quote className="h-4 w-4" />
                </ToolbarButton>

                <div className="w-px bg-gray-300 dark:bg-gray-600 mx-1"></div>

                <ToolbarButton
                    onClick={() => editor.chain().focus().undo().run()}
                    title="Deshacer"
                >
                    <Undo className="h-4 w-4" />
                </ToolbarButton>
                
                <ToolbarButton
                    onClick={() => editor.chain().focus().redo().run()}
                    title="Rehacer"
                >
                    <Redo className="h-4 w-4" />
                </ToolbarButton>
            </div>
            
            <EditorContent 
                editor={editor} 
                className="text-gray-900 dark:text-white"
            />
        </div>
    );
};

export default RichTextEditor;