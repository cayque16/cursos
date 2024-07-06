import { IconButton, TextField } from "@mui/material";
import React, { useRef, useState } from "react";
import DeleteIcon from "@mui/icons-material/Delete";
import UploadIcon from '@mui/icons-material/Upload';

interface Props {
    onAdd: (files: File) => void;
    onRemove: (file: File) => void;
}

export const InputFile: React.FC<Props> = ({ onAdd, onRemove }: Props) => {
    const [selectedFiles, setSelectedFiles] = useState<File>();
    const fileInputRef = useRef<HTMLInputElement>(null);

    const handleChange = (event: React.ChangeEvent<HTMLInputElement>) => {
        const file = event.target.files ? event.target.files[0] : undefined;
        if (!file) return;
        setSelectedFiles(file);
        onAdd(file);
    };

    const handleFileInput = () => {
        fileInputRef.current?.click();
    };

    const handleClear = () => {
        setSelectedFiles(undefined);
        if (selectedFiles) {
            onRemove(selectedFiles)
        }
    };

    return (
        <>
         <TextField
          type="text"
          placeholder="Select a file"
          
          value={selectedFiles?.name || ""}
          InputProps={{
            readOnly: true,
            endAdornment: selectedFiles ? (
                <IconButton onClick={handleClear}>
                    <DeleteIcon />
                </IconButton>
            ) : (
                <IconButton onClick={handleFileInput}>
                    <UploadIcon />
                </IconButton>
            )
          }}
         />
         <input
          accept="*"
          type="file"
          id="inputFile"
          ref={fileInputRef}
          onChange={handleChange}
          style={{ display: "none" }}
         />
        </>
    );
};
