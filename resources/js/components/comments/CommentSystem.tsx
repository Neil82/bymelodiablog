import React, { useState } from 'react';
import { MessageCircle, User, Clock } from 'lucide-react';

interface Comment {
    id: number;
    author: string;
    content: string;
    createdAt: string;
    approved: boolean;
}

interface CommentSystemProps {
    postId: number;
    comments?: Comment[];
    onCommentSubmit?: (comment: { author: string; content: string }) => void;
}

const CommentSystem: React.FC<CommentSystemProps> = ({
    postId,
    comments = [],
    onCommentSubmit
}) => {
    const [newComment, setNewComment] = useState({
        author: '',
        content: ''
    });
    const [isSubmitting, setIsSubmitting] = useState(false);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        if (!newComment.author.trim() || !newComment.content.trim()) return;

        setIsSubmitting(true);
        
        if (onCommentSubmit) {
            await onCommentSubmit(newComment);
        }
        
        setNewComment({ author: '', content: '' });
        setIsSubmitting(false);
    };

    const approvedComments = comments.filter(comment => comment.approved);

    return (
        <div className="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div className="flex items-center gap-2 mb-6">
                <MessageCircle className="h-5 w-5 text-blue-600 dark:text-blue-400" />
                <h3 className="text-xl font-semibold text-gray-900 dark:text-white">
                    Comentarios ({approvedComments.length})
                </h3>
            </div>

            {/* Formulario de nuevo comentario */}
            <form onSubmit={handleSubmit} className="mb-8 space-y-4">
                <div>
                    <label htmlFor="author" className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Nombre
                    </label>
                    <input
                        type="text"
                        id="author"
                        value={newComment.author}
                        onChange={(e) => setNewComment(prev => ({ ...prev, author: e.target.value }))}
                        className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Tu nombre"
                        required
                    />
                </div>
                
                <div>
                    <label htmlFor="content" className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Comentario
                    </label>
                    <textarea
                        id="content"
                        value={newComment.content}
                        onChange={(e) => setNewComment(prev => ({ ...prev, content: e.target.value }))}
                        rows={4}
                        className="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Escribe tu comentario..."
                        required
                    />
                </div>
                
                <div className="text-sm text-gray-500 dark:text-gray-400">
                    Tu comentario será revisado antes de ser publicado.
                </div>
                
                <button
                    type="submit"
                    disabled={isSubmitting}
                    className="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-4 py-2 rounded-md transition-colors"
                >
                    {isSubmitting ? 'Enviando...' : 'Enviar comentario'}
                </button>
            </form>

            {/* Lista de comentarios */}
            <div className="space-y-4">
                {approvedComments.length === 0 ? (
                    <p className="text-gray-500 dark:text-gray-400 text-center py-8">
                        Aún no hay comentarios. ¡Sé el primero en comentar!
                    </p>
                ) : (
                    approvedComments.map((comment) => (
                        <div
                            key={comment.id}
                            className="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-900"
                        >
                            <div className="flex items-center gap-2 mb-2">
                                <User className="h-4 w-4 text-gray-400" />
                                <span className="font-medium text-gray-900 dark:text-white">
                                    {comment.author}
                                </span>
                                <Clock className="h-4 w-4 text-gray-400 ml-auto" />
                                <span className="text-sm text-gray-500 dark:text-gray-400">
                                    {new Date(comment.createdAt).toLocaleDateString()}
                                </span>
                            </div>
                            <div className="text-gray-700 dark:text-gray-300">
                                {comment.content}
                            </div>
                        </div>
                    ))
                )}
            </div>
        </div>
    );
};

export default CommentSystem;